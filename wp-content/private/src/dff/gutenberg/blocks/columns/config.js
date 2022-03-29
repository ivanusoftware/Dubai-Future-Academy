const config = {
  2: {
    title: 'Two Columns',
    layouts: [
      {
        name: '50 / 50',
        icon: null,
        columns: [
          { className: 'span-6--small span-12', width: 50 },
          { className: 'span-6--small span-12', width: 50 },
        ],
      },
      {
        name: '66 / 33',
        icon: null,
        columns: [
          { className: 'span-8--small span-12', width: (100 / 3) * 2 },
          { className: 'span-4--small span-12', width: 100 / 3 },
        ],
      },
      {
        name: '33 / 66',
        icon: null,
        columns: [
          { className: 'span-4--small span-12', width: 100 / 3 },
          { className: 'span-8--small span-12', width: (100 / 3) * 2 },
        ],
      },
    ],
  },
  3: {
    title: 'Three Columns',
    layouts: [
      {
        name: '33 / 33 / 33',
        icon: null,
        columns: [
          { className: 'span-4--small span-12', width: 100 / 3 },
          { className: 'span-4--small span-12', width: 100 / 3 },
          { className: 'span-4--small span-12', width: 100 / 3 },
        ],
      },
      {
        name: '25 / 25 / 50',
        icon: null,
        columns: [
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-6--medium span-12', width: 50 },
        ],
      },
      {
        name: '50 / 25 / 25',
        icon: null,
        columns: [
          { className: 'span-6--medium span-12', width: 50 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
        ],
      },
      {
        name: '25 / 50 / 25',
        icon: null,
        columns: [
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-6--medium span-12', width: 50 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
        ],
      },
    ],
  },
  4: {
    title: 'Four Columns',
    layouts: [
      {
        name: '25 / 25 / 25 / 25',
        icon: null,
        columns: [
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
          { className: 'span-3--medium span-6--small span-12', width: 25 },
        ],
      },
    ],
  },
  5: {
    title: 'Five Columns',
    layouts: [
      {
        name: '20 / 20 / 20 / 20 / 20',
        icon: null,
        columns: [
          { className: 'span-2--medium span-4--small span-6--x-small span-12', width: 20 },
          { className: 'span-2--medium span-4--small span-6--x-small span-12', width: 20 },
          { className: 'span-2--medium span-4--small span-6--x-small span-12', width: 20 },
          { className: 'span-2--medium span-4--small span-6--x-small span-12', width: 20 },
          { className: 'span-2--medium span-4--small span-6--x-small span-12', width: 20 },
        ],
      },
    ],
  },
};

export default config;
