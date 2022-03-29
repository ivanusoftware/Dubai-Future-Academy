module.exports = {
    entry: './blocks/block.js',
    output: {
        path: __dirname,
        filename: 'blocks/block.build.js',
    },
    module: {
        loaders: [
            {
                test: /.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
        ],
    },
};