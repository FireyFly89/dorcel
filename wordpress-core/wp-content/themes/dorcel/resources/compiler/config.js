module.exports = {
    context: 'assets',
    entry: {
        styles: '../../style.less',
        scripts: '../../assets/scripts/main.js',
    },
    devtool: 'cheap-module-eval-source-map',
    outputFolder: '../assets',
    publicFolder: 'dorcel',
    proxyTarget: 'http://dorcel.local',
    watch: [
        '../**/*.php'
    ]
};
