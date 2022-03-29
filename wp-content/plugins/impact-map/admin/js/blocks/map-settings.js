import range from "lodash/range";
const { __ } = wp.i18n;
const { withSelect, withDispatch } = wp.data;
const { Component, Fragment } = wp.element;
const { compose } = wp.compose;
const { InnerBlocks, InspectorControls, RichText, BlockControls, ColorPalette, AlignmentToolbar, BlockAlignmentToolbar, PanelColorSettings } = wp.editor;
const { Button, ButtonGroup, Tooltip,  TabPanel,CheckboxControl, Dashicon, PanelBody, TextControl, RangeControl, ToggleControl, SelectControl, ServerSideRender } = wp.components;


class MapSettings extends Component {

    render() {
        const { showZoom, showFilter,showProjectDetail,pdImageSlider,
            pdChangeImageafter,
            pdProjectTechnologies,
            pdProjectPartners,
            pdProjectStatus,
            pdProjectDescriptions,
            pdProjectAddress,
            pdSharingOptions,
            MapLatitude,
            MapLongitude,
            MapZoom,
            pdShowVIdeo,
            exhibitionMode,changeProjectafter,PrimaryTechnology,Description,SharingOptions,pButton,CompletedRadioButton,ProjectTechnologies,ProjectPartners,autoBackgroundImage, autoBackgroundOverlayColor, autoBackgroundOverlayOpacity, autoHeaderTitleTextColor, autoHeaderSubTitleTextColor} = this.props.AttributesData;
        const setAttributes = this.props.setAttributes;

        const HouseholdAmts = ['0','25000', '35000', '45000', '55000', '65000', '75000', '85000', '95000', '105000', '115000', '130000', '145000', '160000', '175000', '200000', '225000', '250000+'];
        const HoueholdOptions = HouseholdAmts.map( ( item,index ) => {
            return { value: item, label:  item  }
        } );

        const  changeHouseholdAmount = (value) =>{
            const index = HouseholdAmts.indexOf(value);

            setAttributes({annualHouseholdIncome: value });

            setAttributes({annualHouseholdKey: index});

        };

        return (
            <Fragment>
                
                <PanelBody title={ __('Map General Settings') } initialOpen={ false } className="gmap-setting-panel hide-panel">
                    { !exhibitionMode && ( 
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Show Zoom in/ Zoom out' )}</strong>
                            </p>}
                            checked={showZoom}
                            onChange={() => setAttributes( { showZoom: !showZoom } )}
                        />
                    ) }
                    
                    { !exhibitionMode && ( 
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Show Filter' )}</strong>
                            </p>}
                            checked={showFilter}
                            onChange={() => setAttributes( { showFilter: !showFilter } )}
                        />
                    ) }

                <div className="change-lat-long-wrapper">
                    <strong>{__( 'Map Latitude' )}</strong>
                    <TextControl
                        type="text"
                        name="test_name[]"
                        value={MapLatitude}
                        placeholder={__( 'Map Latitude' )}
                        onChange={( value ) => setAttributes( { MapLatitude: value } )}
                    />
                </div>  

                <div className="change-lat-long-wrapper">
                    <strong>{__( 'Map Longitude' )}</strong>
                    <TextControl
                        type="text"
                        name="test_name[]"
                        value={MapLongitude}
                        placeholder={__( 'Map Longitude' )}
                        onChange={( value ) => setAttributes( { MapLongitude: value } )}
                    />
                </div> 

                <div className="change-lat-long-wrapper">
                    <strong>{__( 'Map Zoom' )}</strong>
                    <TextControl
                        type="number"
                        name="test_name[]"
                        value={MapZoom}
                        placeholder={__( 'Map Zoom' )}
                        onChange={( value ) => setAttributes( { MapZoom: value } )}
                    />
                </div> 

                </PanelBody>
                
                <PanelBody title={__("Exhibition Settings")} initialOpen={false} className="gmap-setting-panel hide-panel">
                < ToggleControl
								label={<p>
									<strong>{__( 'Exhibition Mode' )}</strong>
								</p>}
								checked={exhibitionMode}
								onChange={() => setAttributes( { exhibitionMode: !exhibitionMode } )}
							/>
                            <div className="exhibition-wrapper">
                            <TextControl
					type="text"
					name="test_name[]"
					value={changeProjectafter}
					placeholder={__( 'Change Project after' )}
					onChange={( value ) => setAttributes( { changeProjectafter: value } )}
				/>
                           
                            <span>{__( 'Seconds' )}</span>
                            </div> 
                </PanelBody>
                
                <PanelBody title={__("Project Popup Setting")} initialOpen={false} className="gmap-setting-panel hide-panel">
                < ToggleControl
                    label={<p>
                        <strong>{__( 'Primary Technology' )}</strong>
                    </p>}
                    checked={PrimaryTechnology}
                    onChange={() => setAttributes( { PrimaryTechnology: !PrimaryTechnology } )}
                />  
                 < ToggleControl
                    label={<p>
                        <strong>{__( 'Description' )}</strong>
                    </p>}
                    checked={Description}
                    onChange={() => setAttributes( { Description: !Description } )}
                />
                { !exhibitionMode && ( 
                 <ToggleControl
                    label={<p>
                        <strong>{__( 'Sharing Options' )}</strong>
                    </p>}
                    checked={SharingOptions}
                    onChange={() => setAttributes( { SharingOptions: !SharingOptions} )}
                />
                ) }
                { !exhibitionMode && (    
                 < ToggleControl
                    label={<p>
                        <strong>{__( 'Button to Hide "Explore More" button' )}</strong>
                    </p>}
                    checked={pButton}
                    onChange={() => setAttributes( { pButton: !pButton } )}
                />
                ) }  
               
                </PanelBody>

                { !exhibitionMode && (
                <PanelBody title={__("Filter Setting Options")} initialOpen={false} className="gmap-setting-panel hide-panel">
                < ToggleControl
                    label={<p>
                        <strong>{__( 'Completed RadioButton' )}</strong>
                    </p>}
                    checked={CompletedRadioButton}
                    onChange={() => setAttributes( { CompletedRadioButton: !CompletedRadioButton } )}
                />  
                 < ToggleControl
                    label={<p>
                        <strong>{__( 'Project Technologies' )}</strong>
                    </p>}
                    checked={ProjectTechnologies}
                    onChange={() => setAttributes( { ProjectTechnologies: !ProjectTechnologies } )}
                />  
                 < ToggleControl
                    label={<p>
                        <strong>{__( 'Project Partners' )}</strong>
                    </p>}
                    checked={ProjectPartners}
                    onChange={() => setAttributes( { ProjectPartners: !ProjectPartners } )}
                />  
                </PanelBody>
                )}
                
                { !exhibitionMode && pButton && (
                    <PanelBody title={__("Project Detail Setting")} initialOpen={false} className="gmap-setting-panel hide-panel">
                        < ToggleControl
                                        label={<p>
                                            <strong>{__( 'Image Slider' )}</strong>
                                        </p>}
                                        checked={pdImageSlider}
                                        onChange={() => setAttributes( { pdImageSlider: !pdImageSlider } )}
                                    />
                                    <div className="change-image-wrapper">
                                    <TextControl
                            type="text"
                            name="test_name[]"
                            value={pdChangeImageafter}
                            placeholder={__( 'ChangeImage after' )}
                            onChange={( value ) => setAttributes( { pdChangeImageafter: value } )}
                        />
                                
                                    <span>{__( 'Seconds' )}</span>
                                    </div>  
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Project Technologies' )}</strong>
                            </p>}
                            checked={pdProjectTechnologies}
                            onChange={() => setAttributes( { pdProjectTechnologies: !pdProjectTechnologies } )}
                        />  
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Project Partners' )}</strong>
                            </p>}
                            checked={pdProjectPartners}
                            onChange={() => setAttributes( { pdProjectPartners: !pdProjectPartners } )}
                        />  
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Project Status' )}</strong>
                            </p>}
                            checked={pdProjectStatus}
                            onChange={() => setAttributes( { pdProjectStatus: !pdProjectStatus } )}
                        />  

                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Project Descriptions' )}</strong>
                            </p>}
                            checked={pdProjectDescriptions}
                            onChange={() => setAttributes( { pdProjectDescriptions: !pdProjectDescriptions } )}
                        />      
                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Project Address' )}</strong>
                            </p>}
                            checked={pdProjectAddress}
                            onChange={() => setAttributes( { pdProjectAddress: !pdProjectAddress } )}
                        />      

                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Sharing Options' )}</strong>
                            </p>}
                            checked={pdSharingOptions}
                            onChange={() => setAttributes( { pdSharingOptions: !pdSharingOptions } )}
                        />      

                        < ToggleControl
                            label={<p>
                                <strong>{__( 'Show Video' )}</strong>
                            </p>}
                            checked={pdShowVIdeo}
                            onChange={() => setAttributes( { pdShowVIdeo: !pdShowVIdeo } )}
                        />   
                    
                    </PanelBody>
                ) }

            </Fragment>
            
        );
    }
}

export default MapSettings;