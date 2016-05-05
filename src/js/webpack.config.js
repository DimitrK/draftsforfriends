var rucksack = require('rucksack-css');
var webpack = require('webpack');
var path = require('path');

module.exports = {
	context: path.join(__dirname, './app'),
	entry: {
		jsx: './index.js',
		vendor: [
      'react',
      'react-dom',
      'react-redux',
      'redux'
    ]
	},
	output: {
		path: path.join(__dirname, './dist'),
		filename: 'bundle.js',
	},
	devtool: 'source-map',
	module: {
		preLoaders: [
			{
				test: /\.jsx?$/,
				loaders: ['jshint'],
				// define an include so we check just the files we need
				include: /app/
      }
    ],
		loaders: [
			{
				test: /\.css$/,
				include: /app/,
				loaders: [
          'style-loader',
          'css-loader?modules&sourceMap&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]',
          'postcss-loader'
        ]
      },
			{
				test: /\.css$/,
				exclude: /app/,
				loader: 'style!css'
      },
			{
				test: /\.(js|jsx)$/,
				include: [path.resolve(__dirname, "app")],
				loaders: [
          'babel-loader'
        ],
      },
  	],
	},
	resolve: {
		extensions: ['', '.js', '.jsx']
	},
	postcss: [
			rucksack({
			autoprefixer: true
		})
		],
	plugins: [
			new webpack.optimize.CommonsChunkPlugin('vendor', 'vendor.bundle.js'),
			new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development')
			}
		})
	]

};