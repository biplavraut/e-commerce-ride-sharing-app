let mix = require("laravel-mix");

switch (process.env.section) {
  case 'vendor':
    require(`${__dirname}/webpack.mix.vendor.js`);
    break;
  default:
    require(`${__dirname}/webpack.mix.admin.js`);
    break;
}

mix.disableNotifications();
