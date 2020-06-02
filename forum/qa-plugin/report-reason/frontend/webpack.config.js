const path = require('path');

module.exports = ({development}) => {
    return {
        mode: development ? 'development' : 'production',
        entry: './index.js',
        output: {
            path: path.resolve( __dirname, 'dist' ),
            filename: 'script.js',
            publicPath: '../qa-plugin/report-reason/frontend/dist/'
        },
        module: {
            rules: [
                {
                    test: /\.js$/, exclude: /node_modules/, loader: 'babel-loader',
                }
            ]
        },
        devtool: development ? 'source-map' : 'none',
        watch: !!development
    };
}
