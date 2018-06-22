var webpack = require("webpack");
var webpackMerge = require("webpack-merge");
var MiniCssExtractPlugin = require("mini-css-extract-plugin");
var commonConfig = require("./webpack.common.js");
var helpers = require("./helpers");
var targetUrl = require("./target.js");

const ENV = process.env.NODE_ENV = process.env.ENV = "live";

module.exports = webpackMerge(commonConfig, {
	mode: "production",
	output: {
		path: helpers.root("public_html/dist"),
		publicPath: "dist",
		filename: "[name].[hash].js",
		chunkFilename: "[id].[hash].chunk.js"
	},

	performance: {
		hints: false
	},

	plugins: [
		new webpack.NoEmitOnErrorsPlugin(),
		new MiniCssExtractPlugin({filename: "[name].[hash].css"}),
		new webpack.DefinePlugin({
			"process.env": {
				"BASE_HREF": JSON.stringify(targetUrl().substring(targetUrl().indexOf("/", targetUrl().indexOf("//") + 2))),
				"ENV": JSON.stringify(ENV)
			}
		}),
		new webpack.LoaderOptionsPlugin({
			htmlLoader: {
				minimize: false // workaround for ng2
			}
		})
	]
});