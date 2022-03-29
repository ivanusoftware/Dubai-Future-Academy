module.exports = {
    entry: './js/events-block.js',
    output: {
        path: __dirname,
        filename: 'events-block.build.js',
    },
    module: {
        rules: [
            {
                test: /.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
        ],
    },
};
