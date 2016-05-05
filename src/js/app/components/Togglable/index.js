'use strict';
import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import Link from '../Link';
import style from './style.css';

class Togglable extends Component {
	
	constructor(props) {
		super(props);
		this.state = { open: false };
	}
	
	shouldComponentUpdate(nextState, nextProps) {
		return nextState.open !== this.state.open;
	}
	
	handleOpenFormClick() {
		this.setState( { open: true } );
	}
	
	handleCloseFormClick() {
		this.setState( { open: false } );
	}
	
	renderOpenExtender() {
		const { view, hideText } = this.props;
		
		return (
			<p className={ style['inline-group'] }>
			
				{ view }
			
				<Link onClick={ ::this.handleCloseFormClick } >
					{ hideText }
				</Link>
			
			</p>
		); 
	}
	
	renderClosedExtender() {
		const { showText } = this.props;
		
		return (
				<Link
					className={ style['draftsforfriends-extend'] }
					onClick={ ::this.handleOpenFormClick }
				>
				{ showText }
				</Link>
			);
	}
	
	render() {
		if ( this.state.open ) {
			return this.renderOpenExtender();
		}
		
		return this.renderClosedExtender();
	}
}


Togglable.propTypes = {
	view		: PropTypes.node.isRequired,
	showText: PropTypes.string,
	hideText: PropTypes.string
}

Togglable.defaultProps = {
	showText: 'Show',
	hideText: 'Hide',
}

export default Togglable;
