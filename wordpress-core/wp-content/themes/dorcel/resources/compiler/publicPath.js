const path = require('path');

module.exports = (folder, prefix = 'wordpress-core') => {
    const theme = path.basename(path.resolve('../../'));

    return `${prefix}/wp-content/${theme}/${folder}/`;
};
