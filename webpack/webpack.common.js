var webpack = require("webpack");
var HtmlWebpackPlugin = require("html-webpack-plugin");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var helpers = require("./helpers");

module.exports = {
	entry: {
		"polyfills": helpers.root("src") + "/polyfills.ts",
		"vendor": helpers.root("src") + "/vendor.ts",
		"app": helpers.root("src") + "/main.ts",
		"css": helpers.root("src") + "/app.css"
	},

	resolve: {
		extensions: [".ts", ".js"]
	},

	module: {
		rules: [
			{
				test: /\.(html|php)$/,
				loader: "html-loader"
			},
			{
				test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
				loader: "url-loader?name=/assets/[name].[hash].[ext]"
			},
			{
				test: /\.css$/,
				loader: ExtractTextPlugin.extract({ fallback: "style-loader", use: ["css-loader?minimize=true"] })
			},
			{
				test: /\.ts$/,
				loaders: ["awesome-typescript-loader"]
			}
		]
	},

	plugins: [
		new webpack.optimize.CommonsChunkPlugin({
			name: ["app", "vendor", "polyfills"]
		}),

		new webpack.ContextReplacementPlugin(
			// The (\\|\/) piece accounts for path separators in *nix and Windows
			// For Angular 5, see also https://github.com/angular/angular/issues/20357#issuecomment-343683491
			/\@angular(\\|\/)core(\\|\/)esm5/,
			helpers.root("src"), // location of your src
			{
				// your Angular Async Route paths relative to this root directory
			}
		),

		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			"window.jQuery": "jquery",
			Popper: ['popper.js', 'default']
		}),

		new webpack.ContextReplacementPlugin(/@angular(\\|\/)core(\\|\/)/, helpers.root("src")),

		new HtmlWebpackPlugin({
			inject: "head",
			filename: helpers.root("public_html") + "/index.html",
			template: helpers.root("webpack") + "/index.ejs"
		})
	]
};