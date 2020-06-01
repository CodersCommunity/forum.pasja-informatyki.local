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
        devtool: 'source-map',
        watch: !!development
    };
}
