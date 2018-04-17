const autoprefixer = require('autoprefixer');
const webpack = require('webpack');
const path = require('path');
const precss = require('precss');

// For extending library
const TransferWebpackPlugin = require('transfer-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
	// Set develop tool, including of eval and source-map, etc..
	devtool: 'eval',
	entry: [
		'font-awesome/scss/font-awesome.scss',
		'./app/module.js'
	],
	output: {
		path: path.resolve(__dirname, "public"),
		filename: 'bundle.js',
		publicPath: './'
	},
	module: {
		rules: [			
			/** React node_modules plugins **/
			// {
			// 	test: /\.jsx?$/,
			// 	exclude: /node_modules/,
			// 	loader: 'babel-loader',
			// 	query: {
          	//		cacheDirectory: true,
        	//	},
			// }
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: "babel-loader",
				query: {
					presets: ["es2015"]
				}
			},
			{
				test: /\.css$/,
				use: ["style-loader", "css-loader", "postcss-loader"]
			},
			{
				test: /\.scss$/,
				use: ExtractTextPlugin.extract({
					fallback: 'style-loader',
					use: [
						{
							loader: 'css-loader'
						},
						{
							loader: 'postcss-loader',
							options: {
								plugins() {
									/** post css plugins can be exported to postcss.config.js **/
									return [
										precss,
										autoprefixer
									];
								}
							}
						},
						{
							loader: 'sass-loader'
						}
					]
				})
			},
			{
				test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        		use: 'url-loader?limit=10000'
			},
			{
				test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
        		use: 'file-loader'
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,		
        		loader: 'url-loader?limit=8192'
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
      			loader: 'file-loader?hash=sha512&digest=hex&name=[path][name]-[hash].[ext]'
			},
			{
				test: /font-awesome\.config\.js/,
				use: [
					{ 
						loader: 'style-loader' 
					},
					{
						loader: 'font-awesome-loader'
					}
				]
			},
			{
				// bootstrap 4
				test: /bootstrap\/dist\/js\/umd\//,
				use: 'imports-loader?jQuery=jquery'
			},
			{
				test: require.resolve('angular'),
				loader: 'exports-loader?window.angular'
			},
			{
				test: /\.html$/,
				loader: 'html-loader'
			}
		]
	},
	plugins: [
		/** new webpack HotModuleReplacementPlugin() **/ 
		new webpack.ProvidePlugin({
			angular: 'angular',
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery',
			Popper: ['popper.js', 'default'],
			Alert: 'exports-loader?Alert!bootstrap/js/dist/alert',
			Button: 'exports-loader?Button/bootstrap/js/dist/button',
			Carousel: 'exports-loader?Carousel!bootstrap/js/dist/carousel',
			Collapse: 'exports-loader?Collapse!bootstrap/js/dist/collapse',
			Dropdown: 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
			Modal: 'exports-loader?Modal!bootstrap/js/dist/modal',
			Popover: 'exports-loader?Popover!bootstrap/js/dist/popover',
			Scrollspy: 'exports-loader?Scrollspy!bootstrap/js/dist/scrollspy',
			Tab: 'exports-loader?Tab!bootstrap/js/dist/tab',
			Tooltip: 'exports-loader?Tooltip!bootstrap/js/dist/tooltip',
			Util: 'exports-loader?Util!bootstrap/js/dist/util'
		}),
		new ExtractTextPlugin("bundle.css"),
	]
};


