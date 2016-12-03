const config = {
    entry: './assets/js/main.js',
    output: {
        filename: 'bundle.js',
        path: './webroot/js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            }
        ]
    }
};

export default config;
