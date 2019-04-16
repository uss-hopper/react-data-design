const proxy = require('http-proxy-middleware');
var username = "gkephart";


//TODO Need to find a dry way of managing proxy config
module.exports = function(app) {
	app.use(proxy('/apis', {
			target: 'https://bootcamp-coders.cnm.edu/~' + username + '/react/php/public_html/',
			"secure": false,
			"changeOrigin": "true"
		})
	);
};