/**
 * BLOCK: rightSideBar Row / Layout
 */

/**
 * Import External
 */
//import map from 'lodash/map';
import classnames from 'classnames';
import MapSettings from './map-settings';
import attributes from './map-attributes';


const { Component, Fragment } = wp.element;
const { InnerBlocks, InspectorControls, RichText } = wp.editor;
const { PanelBody, Button, ButtonGroup,TextControl,CheckboxControl,ServerSideRender } = wp.components;
const { __ } = wp.i18n;

const TwoColumnTemplate = [['core/columns', { columns: 2 }]];


//import { Map, GoogleApiWrapper } from 'google-maps-react';


const sizeTypes = [
    { key: 'h1', name: __('H1') },
    { key: 'h2', name: __('H2') },
    { key: 'h3', name: __('H3') },
    { key: 'h4', name: __('H4') },
    { key: 'h5', name: __('H5') },
    { key: 'h6', name: __('H6') },
];


/**
 * Build the row edit
 */
class rightSideBarRowLayout extends Component {
	constructor() {
        super( ...arguments );
        this.state = {
            taxonomies: [],
            taxonomies_name: [],
            termsObj: {},
            filterTermsObj: {},
            showingInfoWindow: false,
            activeMarker: {},
            selectedPlace: {},
           
        };
       

    }
 
    componentDidMount() {
        wp.apiFetch({ path: '/wp/v1/all-terms' }).then(terms => {
            console.log(terms);
            this.setState({
                termsObj: terms,
                filterTermsObj: terms,
                taxonomies: ['project_partners', 'project_technologies'],
                taxonomies_name: ['Project Partners', 'Project Technologies'],
            });

        });

    }
    isEmpty(obj) {
        let key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    }
    filterTerms(value, taxonomy) {
        let filterTerms = {};
        this.state.taxonomies.map(tax => {
            if (taxonomy === tax) {
                filterTerms[tax] = this.state.termsObj[tax].filter(
                    term => -1 < term.name.toLowerCase().indexOf(value.toLowerCase())
                );
            } else {
                filterTerms[tax] = this.state.termsObj[tax];
            }
        });
        this.setState({ filterTermsObj: filterTerms });
    }
    getPosts(value) {
        const idsStr = this.state.ids;
        const idsArray = idsStr.split(',');
        if (-1 !== idsArray.indexOf(value.toString())) {
            idsArray.splice(idsArray.indexOf(value.toString()), 1);
        } else {
            idsArray.push(value.toString());
        }
        const resultIds = idsArray.join();
        this.setState({ ids: resultIds });
    }
    render() {
       
        const { attributes: {title,
            showZoom,
            showFilter,
            showProjectDetail,
            exhibitionMode,
            changeProjectafter,
            MapLatitude,
            MapLongitude,
            MapZoom,
            PrimaryTechnology,
            Description,
            SharingOptions,
            pButton,
            CompletedRadioButton,
            ProjectTechnologies,
            ProjectPartners,
            pdImageSlider,
            pdChangeImageafter,
            pdProjectTechnologies,
            pdProjectPartners,
            pdProjectStatus,
            pdProjectDescriptions,
            pdProjectAddress,
            pdSharingOptions,
            pdShowVIdeo,
            terms,
            taxonomies,
            tempTerms,
            autoBackgroundImage, 
            autoBackgroundOverlayColor,
             autoBackgroundOverlayOpacity, 
             autoHeaderTitleTextColor, 
             autoHeaderSubTitleTextColor, 
            }, setAttributes, clientId } = this.props;
       
        let isCheckedTerms = {};
        if (!this.isEmpty(terms) && terms.constructor !== Object) {
            isCheckedTerms = JSON.parse(terms);
        }
        console.log(this.props.attributes);
        const backgroundStyle = {};
        autoBackgroundImage && (backgroundStyle.backgroundImage = `url(${autoBackgroundImage})` );

        const headerTitleStyle = {};
        autoHeaderTitleTextColor && (headerTitleStyle.color = autoHeaderTitleTextColor);

        const headerSubTitleStyle = {};
        autoHeaderSubTitleTextColor && (headerSubTitleStyle.color = autoHeaderSubTitleTextColor);

        const setInsurer = event => {
            const selected = event.target.querySelector( 'option:checked' );
            setAttributes( { currentlyInsurer: selected.value } );
            event.preventDefault();
        };

        const onSelectOwnHome = event => {
            const selectedHome = event.target.value;
            const selectedClass = event.target.className;
            'components-button rectBtn own-home active' === selectedClass && setAttributes({ ownHome: '' });
            'components-button rectBtn own-home active' !== selectedClass && setAttributes({ ownHome: selectedHome ? selectedHome : '' });
        };

        const onSelectCreditScore = event => {
            const selectedCreditScore = event.target.value;
            const selectedClass = event.target.className;
            'components-button rectBtn credit-score active' === selectedClass && setAttributes({ creditScore: '' });
            'components-button rectBtn credit-score active' !== selectedClass && setAttributes({ creditScore: selectedCreditScore ? selectedCreditScore : '' });
        };

        const onSelectAccidents = event => {
            const selectedAccident = event.target.value;
            const selectedClass = event.target.className;
            'components-button rectBtn accident-ticket active' === selectedClass && setAttributes({ accident: '' });
            'components-button rectBtn accident-ticket active' !== selectedClass && setAttributes({ accident: selectedAccident ? selectedAccident : '' });
        };

        const onSelectSr22 = event => {
            const selectedSr22 = event.target.value;
            const selectedClass = event.target.className;
            'components-button rectBtn sr22 active' === selectedClass && setAttributes({ sr22: '' });
            'components-button rectBtn sr22 active' !== selectedClass && setAttributes({ sr22: selectedSr22 ? selectedSr22 : '' });
        };

        const onSelectDui = event => {
            const selectedDui = event.target.value;
            const selectedClass = event.target.className;
            'components-button rectBtn dui active' === selectedClass && setAttributes({ dui: '' });
            'components-button rectBtn dui active' !== selectedClass && setAttributes({ dui: selectedDui ? selectedDui : '' });
        };
       const onMarkerClick = (props, marker, e) =>
      
        this.setState({
          selectedPlace: props,
          activeMarker: marker,
          showingInfoWindow: true
        });
     
     const onMapClicked = (props) => {
        
        if (this.state.showingInfoWindow) {
          this.setState({
            showingInfoWindow: false,
            activeMarker: null
          })
        }
      };
      
        return (
            <Fragment>


                {(
                    <InspectorControls>
                        <MapSettings AttributesData={this.props.attributes} setAttributes={setAttributes}/>
                        <div className='tag-category-wrapper'>
                                                {0 < this.state.taxonomies.length && (
                                                    <Fragment>
                                                        {this.state.taxonomies.map(
                                                            (taxonomy, index) =>
                                                                undefined !== this.state.filterTermsObj[taxonomy] && (
                                                                    <div className='faq-tag'>
                                                                        <div className='tag-wrapper'>
                                                                            {/* <div className='input-faq'>
                                                                                <i className="faq-selection-arrow-icon dashicons dashicons-arrow-down-alt2" onClick={(e) => e.currentTarget.parentElement.classList.toggle('faq-selection-arrow-active')}></i>
                                                                                <TextControl
                                                                                    type="string"
                                                                                    name={taxonomy}
                                                                                    placeholder={`Search ${taxonomy}`}
                                                                                    onChange={value => this.filterTerms(value, taxonomy)}
                                                                                />
                                                                            </div> */}
                         <PanelBody title={ __(this.state.taxonomies_name[index]) } initialOpen={ false } className="hide-panel">                                      
                        <ul className='dropdown-list tag-dropdown-list'>
                            <li role='option' className='dropdown-item'>
                                <span className='filterOption'>
                                    {this.state.filterTermsObj[taxonomy].map(
                                        (term, index) => (
                                            <Fragment key={index}>
                                                <CheckboxControl
                                                    checked={
                                                        isCheckedTerms[taxonomy] !== undefined &&
                                                        -1 <
                                                        isCheckedTerms[taxonomy].indexOf(
                                                            term.slug
                                                        )
                                                    }
                                                    label={term.name}
                                                    name={`${taxonomy}[]`}
                                                    value={term.slug}
                                                    className='checkbox-input'
                                                    onChange={isChecked => {
                                                        let index,
                                                            tempTerms = terms;
                                                        if (!this.isEmpty(tempTerms)) {
                                                            tempTerms = JSON.parse(tempTerms);
                                                        }
                                                        if (isChecked) {
                                                            if (tempTerms[taxonomy] === undefined) {
                                                                tempTerms[taxonomy] = [term.slug];
                                                            } else {
                                                                tempTerms[taxonomy].push(term.slug);
                                                            }
                                                        } else {
                                                            index = tempTerms[taxonomy].indexOf(
                                                                term.slug
                                                            );
                                                            tempTerms[taxonomy].splice(index, 1);
                                                        }
                                                        tempTerms = JSON.stringify(tempTerms);
                                                        this.props.setAttributes({
                                                            terms: tempTerms
                                                        });
                                                        this.getPosts(term.term_id);
                                                    }}
                                                />
                                            </Fragment>
                                        )
                                    )}
                                </span>
                            </li>
                        </ul>
                        </PanelBody>                                            
                         </div>
                                                                  
                        </div>
                                                                )
                                                        )}
                                                    </Fragment>
                                                )}
                                            </div> 
                                          
                    </InspectorControls>
                )}
                {(
                    <div className="map-block" style={ backgroundStyle } id={ 'map-block_' + clientId }>
                        <ServerSideRender
                            block="imap/imap"
                            attributes={this.props.attributes}
                            onChange={googleMapUpdated(this.props.attributes)}
                        />
                    </div>                     
                )}   
            </Fragment>
        )
    }
}
//rightSideBarRowLayout.defaultProps = googleMapStyles;
export default (rightSideBarRowLayout);
