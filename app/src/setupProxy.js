const proxy = require('http-proxy-middleware');

module.exports = function(app) {
	app.use(proxy('/apis', {
		target: "https://bootcamp-coders.cnm.edu/~gkephart/react/php/public_html/",
		changeOrigin: true,
		secure: true,

	}));
};