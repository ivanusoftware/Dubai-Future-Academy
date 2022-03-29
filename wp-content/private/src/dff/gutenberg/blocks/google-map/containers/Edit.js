/* eslint-disable no-loop-func */
/* eslint-disable react/destructuring-assignment */
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { PanelBody, RangeControl, TextControl, Button } = wp.components;
const { InspectorControls } = wp.editor;

class Edit extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      allMarkers: [],
    };
  }

  componentDidMount() {
    if (document.getElementById('map') === null) {
      return;
    }

    window.geocoder = new window.google.maps.Geocoder();
    window.map = new window.google.maps.Map(document.getElementById('map'));
    this.refreshMap();
  }

  createUpdateAttribute = key => value => this.props.setAttributes({ [key]: value });

  /**
   * Listener to correct zoom after fitBounds.
   */
  zoomCorrection = () => {
    const zoomAmount = this.props.attributes.zoom;
    const listener = window.google.maps.event.addListener(window.map, 'idle', () => {
      if (window.map.getZoom() > zoomAmount) {
        window.map.setZoom(zoomAmount);
      }

      window.google.maps.event.removeListener(listener);
    });
  };

  createInfoWindow = location => {
    // Convert the location into an array.
    const infoWindowLocation = location.split(',');

    // Split the array to get the first line and the rest separated.
    // First line will be the title. Note: muatation on infoWindowLocation. Desired.
    const infoWindowTitle = infoWindowLocation.shift();

    const infoWindowString = `
      <div class="poi-info-window gm-style">
        <div class="title full-width">${infoWindowTitle}</div>
        <div class="address">
          ${infoWindowLocation
            .map(line => `<div class="address-line full-width">${line}</div>`)
            .join('')}
        </div>
      </div>
    `;

    return new window.google.maps.InfoWindow({
      content: infoWindowString,
    });
  };

  updateZoom(value) {
    const { setAttributes } = this.props;
    window.map.setZoom(value);
    setAttributes({ zoom: value });
  }

  updateHeight(value) {
    const { setAttributes } = this.props;
    window.map.setZoom(value);
    setAttributes({ height: value });
  }

  clearOverlays() {
    const { setAttributes } = this.props;
    const { allMarkers } = this.state;

    if (allMarkers.length >= 1) {
      for (let i = 0; i < allMarkers.length; i += 1) {
        allMarkers[i].setMap(null);
        allMarkers[i] = null;
      }
    }
    setAttributes({ markers: [] });
    this.setState({ allMarkers: [] });
  }

  refreshMap() {
    const { attributes, setAttributes } = this.props;
    this.clearOverlays();

    const { addressOne } = attributes;
    const addresses = [addressOne];
    let i = 0;
    const allMarkers = [];
    const allMarkersState = [];
    const { geocoder, map, google } = window;

    // Create empty LatLngBounds object.
    const bounds = new google.maps.LatLngBounds();

    for (i = 0; i < addresses.length; i += 1) {
      const infoWindow = this.createInfoWindow(addresses[i]);

      geocoder.geocode({ address: addresses[i] }, (results, status) => {
        if (status === google.maps.GeocoderStatus.OK) {
          const marker = new google.maps.Marker({
            map,
            position: results[0].geometry.location,
          });

          marker.addListener('click', () => {
            infoWindow.open(map, marker);
          });

          allMarkersState.push(marker);

          const markerObj = {
            position: results[0].geometry.location,
          };
          allMarkers.push(markerObj);

          bounds.extend(marker.position);
          map.fitBounds(bounds);
          this.zoomCorrection();
        }
      });
    }

    setAttributes({ markers: allMarkers });
    this.setState({ allMarkers: allMarkersState });
  }

  render() {
    const { attributes, setAttributes } = this.props;
    const { addressOne, zoom, height } = attributes;

    if (!window.google) {
      return <p>Please add an API Key</p>;
    }

    const divStyle = {
      height: `${height}px`,
    };

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={__('Options', 'octopus')}>
            <TextControl
              label="Address"
              value={addressOne}
              onChange={value => {
                setAttributes({ addressOne: value });
              }}
            />
            <Button className="map-refresh" isPrimary onClick={() => this.refreshMap()}>
              Refresh Markers
            </Button>
            <TextControl
              label="Height"
              value={height}
              onChange={value => setAttributes({ height: value })}
            />
            <RangeControl
              beforeIcon="arrow-left-alt2"
              afterIcon="arrow-right-alt2"
              label="zoom"
              value={zoom}
              onChange={value => this.updateZoom(value)}
              min={1}
              max={21}
            />
          </PanelBody>
        </InspectorControls>
        <section>
          <div className="section-wrapper">
            <div id="map" style={divStyle} />
          </div>
        </section>
      </Fragment>
    );
  }
}

export default Edit;
