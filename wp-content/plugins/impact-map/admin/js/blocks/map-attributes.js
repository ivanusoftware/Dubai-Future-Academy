const attributes = {
    title: {
        type: 'string',
        default: '',
    },
    showZoom: {
		type: 'boolean',
		default: true,
    },
    showFilter: {
		type: 'boolean',
		default: true,
    },
    showProjectDetail: {
		type: 'boolean',
		default: true,
    },
    
    exhibitionMode: {
		  type: 'boolean',
		  default: false,
    },

    changeProjectafter: {
        type: 'string',
        default: '5',
    },

    MapLatitude: {
      type: 'string',
      default: '25.2048493',
    },

    MapLongitude: {
      type: 'string',
      default: '55.2707828',
    },

    MapZoom: {
      type: 'string',
      default: '12',
    },

    PrimaryTechnology: {
		type: 'boolean',
		default: true,
    },

    Description: {
		type: 'boolean',
		default: true,
    },

    SharingOptions: {
		type: 'boolean',
		default: true,
    },
    pButton: {
		type: 'boolean',
		default: true,
    },
    
    CompletedRadioButton: {
		type: 'boolean',
		default: true,
    },
    ProjectTechnologies: {
		type: 'boolean',
		default: true,
    },
    ProjectPartners: {
		type: 'boolean',
		default: true,
    },

    pdImageSlider: {
		type: 'boolean',
		default: true,
    },
    pdChangeImageafter: {
        type: 'string',
        default: '20',
    },

    pdProjectTechnologies: {
		type: 'boolean',
		default: true,
    },

    pdProjectPartners: {
		type: 'boolean',
		default: true,
    },

    pdProjectStatus: {
		type: 'boolean',
		default: true,
    },

    pdProjectDescriptions: {
		type: 'boolean',
		default: true,
    },
    
    pdProjectAddress: {
		type: 'boolean',
		default: true,
    },
    pdSharingOptions: {
		type: 'boolean',
		default: true,
    },
    pdShowVIdeo: {
		type: 'boolean',
		default: true,
    },
       
    terms: {
        type: 'string',
        default: {}
    },
    taxonomies: {
        type: 'array',
        default: []
    },
};
export default attributes;
