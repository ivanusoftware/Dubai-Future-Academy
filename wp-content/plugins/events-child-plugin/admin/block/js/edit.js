const {__} = wp.i18n;
const {PanelBody, SelectControl, RangeControl, ToggleControl, CheckboxControl, Toolbar} = wp.components;
const {Component, Fragment} = wp.element;
const{BlockControls, InspectorControls, PanelColorSettings} = wp.blockEditor;
const {serverSideRender: ServerSideRender} = wp;
class Edit extends Component {
    constructor() {
        super( ...arguments );
        this.state = {
            categories: [],
            tags: [],
        };
    }


    componentDidMount() {
        wp.apiFetch( { path: '/dff/get_terms?tax=events_categories' } )
            .then( categories =>{
              let categoryArr = [];
                if(0 < Object.keys(categories).length){
                  Object.keys(categories).map(function(key){
                        categoryArr[key] = categories[key];
                    });
                }
                this.setState({categories: categoryArr});
            });

        wp.apiFetch( { path: '/dff/get_terms?tax=events_tags' } )
            .then( tags => {
                let tagArr = [];
                if(0 < Object.keys(tags).length){
                  Object.keys(tags).map(function(key){
                    tagArr[key] = tags[key];
                  });
                }
                this.setState({tags: tagArr});
            })
    }

    setCategories( catID, willAdd ) {
        const { attributes, setAttributes } = this.props;
        const { checkedCats } = attributes;

        if (willAdd) {
            setAttributes( { checkedCats: [ ...checkedCats, catID ] } );
        } else {
            setAttributes( { checkedCats: checkedCats.filter( (checkedCats) => checkedCats !== catID ) } )
        }

    }
    setTags( tagID, willAdd ) {
        const { attributes, setAttributes } = this.props;
        const { checkedTags } = attributes;

        if (willAdd) {
            setAttributes( { checkedTags: [ ...checkedTags, tagID ] } );
        } else {
            setAttributes( { checkedTags: checkedTags.filter( (checkedTags) => checkedTags !== tagID ) } )
        }

    }
    fetchPosts(){
        wp.apiFetch({
            path: '/wp-media-upload/v1/add-image-metadata',
            method: 'POST',
            data: {postId: selectedID },
        }).then(data => {
            if (data.success) {

            }
        });
    }
    render() {
        const { attributes, setAttributes } = this.props;
        const {bgColor, titleColor, textColor, checkedCats, checkedTags, eLayout, featureImageToggle, paginationToggle, dateTimeToggle, openNewTabToggle, catsToggle, tagsToggle, openUpcomingToggle, orderBy, totalEvents} = attributes;
        console.log(checkedTags);
        return (
            <Fragment>
                <BlockControls>
                    <Toolbar className={'list-view' === eLayout && 'active'}>
                        <button
                            className="components-button layout-toolbar"
                            title="List View"
                            onClick={() => {
                                setAttributes({ eLayout: 'list-view' });
                            }}
                        >
                            <span className="dashicons dashicons-list-view" />
                        </button>
                    </Toolbar>
                    <Toolbar className={'grid-view' === eLayout && 'active'}>
                        <button
                            className="components-button layout-toolbar"
                            title="Grid View"
                            onClick={() => {
                                setAttributes({ eLayout: 'grid-view' });
                            }}
                        >
                            <span className="dashicons dashicons-grid-view" />
                        </button>
                    </Toolbar>
                    <Toolbar className={'card-view' === eLayout && 'active'}>
                        <button
                            className="components-button layout-toolbar"
                            title="Card View"
                            onClick={() => {
                                setAttributes({ eLayout: 'card-view' });
                            }}
                        >
                            <span className="dashicons dashicons-screenoptions" />
                        </button>
                    </Toolbar>
                </BlockControls>
                <InspectorControls>
                    <PanelBody title={__('Events Settings')} initialOpen={true}>
                    <label className="inspector-mb-0">Number of events</label>
                        <RangeControl
                            value={totalEvents}
                            min={1}
                            max={12}
                            onChange={(item) => { setAttributes({ totalEvents: parseInt(item) }); }}
                        />
                    </PanelBody>
                    <PanelBody title={__('Order By')} initialOpen={false}>
                      <SelectControl
                        value={orderBy}
                        options={[
                            { label: __('Newest to Oldest'), value: 'date' },
                            { label: __('Menu Order'), value: 'menu_order' },
                          ]}
                        onChange={(value) => { setAttributes({ orderBy: value }); }}
                      />
                    </PanelBody>
                    <PanelBody title={__('Category')} initialOpen={false}>
                        <div className='limitHeight'>
                        { 0 < this.state.categories.length && this.state.categories.map((item, index) => (
                            <CheckboxControl
                                key={index}
                                label={item.name}
                                className='checkbox-input'
                                checked={ jQuery.inArray(index, checkedCats) > -1 }
                                onChange={ (checked) => this.setCategories( index, checked ) }
                            />
                            ))
                        }
                        </div>
                    </PanelBody>
                    <PanelBody title={__('Tags')} initialOpen={false}>
                        <div className='limitHeight'>
                        { 0 < this.state.tags.length && this.state.tags.map((item, index) => (
                            <CheckboxControl
                                key={index}
                                label={item.name}
                                className='checkbox-input'
                                checked={ jQuery.inArray(index, checkedTags) > -1 }
                                onChange={ (checked) => this.setTags( index, checked ) }
                            />
                        ))
                        }
                        </div>
                    </PanelBody>
                    <PanelColorSettings
                        title={ __( 'Color Settings' ) }
                        initialOpen={ false }
                        colorSettings={ [
                            {
                                label: __( 'Background Color' ),
                                value: bgColor,
                                onChange: ( value ) => setAttributes( { bgColor: value === undefined ? 'transparent' : value } ),
                            },
                            {
                                label: __( 'Title Color' ),
                                value: titleColor,
                                onChange: ( value ) => setAttributes( { titleColor: value === undefined ? '#000' : value } ),
                            },
                            {
                                label: __( 'Text Color' ),
                                value: textColor,
                                onChange: ( value ) => setAttributes( { textColor: value === undefined ? '#000' : value } ),
                            },
                        ] }
                    />
                    <PanelBody title={__('Toggles')} initialOpen={false}>
                        <ToggleControl
                            label={<strong>{__('Show Featured Image')}</strong>}
                            checked={featureImageToggle}
                            onChange={() => setAttributes({featureImageToggle: !featureImageToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Pagination')}</strong>}
                            checked={paginationToggle}
                            onChange={() => setAttributes({paginationToggle: !paginationToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Show Categories')}</strong>}
                            checked={catsToggle}
                            onChange={() => setAttributes({catsToggle: !catsToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Show Tags')}</strong>}
                            checked={tagsToggle}
                            onChange={() => setAttributes({tagsToggle: !tagsToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Show Event Date & Time')}</strong>}
                            checked={dateTimeToggle}
                            onChange={() => setAttributes({dateTimeToggle: !dateTimeToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Show only upcoming Events')}</strong>}
                            checked={openUpcomingToggle}
                            onChange={() => setAttributes({openUpcomingToggle: !openUpcomingToggle})}
                        />
                        <ToggleControl
                            label={<strong>{__('Show detail in a new page')}</strong>}
                            checked={openNewTabToggle}
                            onChange={() => setAttributes({openNewTabToggle: !openNewTabToggle})}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="events-listing" >
                    <ServerSideRender
                        block="dff/events"
                        attributes={{
                            checkedCats: checkedCats,
                            checkedTags: checkedTags,
                            orderBy: orderBy,
                            totalEvents: totalEvents,
                            dateTimeToggle: dateTimeToggle,
                            openNewTabToggle: openNewTabToggle,
                            openUpcomingToggle: openUpcomingToggle,
                            paginationToggle: paginationToggle,
                            catsToggle: catsToggle,
                            tagsToggle: tagsToggle,
                            featureImageToggle: featureImageToggle,
                            eLayout: eLayout,
                            titleColor: titleColor,
                            textColor: textColor,
                            bgColor: bgColor
                        }}
                    />
                </div>
            </Fragment>
        )
    }
}
export default Edit;
