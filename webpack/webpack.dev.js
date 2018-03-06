var webpack = require("webpack");
var webpackMerge = require("webpack-merge");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var commonConfig = require("./webpack.common.js");
var helpers = require("./helpers");
var targetUrl = require("./target.js");

const ENV = process.env.NODE_ENV = process.env.ENV = "dev";

module.exports = webpackMerge(commonConfig, {
	devtool: "cheap-module-eval-source-map",

	output: {
		path: helpers.root("public_html"),
		publicPath: "http://localhost:7272",
		filename: "[name].js",
		chunkFilename: "[id].chunk.js"
	},

	plugins: [
		new ExtractTextPlugin("[name].css"),
		new webpack.DefinePlugin({
			"process.env": {
				"BASE_HREF": JSON.stringify("/"),
				"ENV": JSON.stringify(ENV)
			}
		})
	],

	devServer: {
		contentBase: helpers.root("public_html"),
		historyApiFallback: true,
		stats: "minimal",
		proxy: {
			"/api": {
				target: targetUrl(),
				secure: false
			}
		}
	}
});