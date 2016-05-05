'use strict';

import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import { listSettings } from '../../actions/setting-actions';
import Loader from '../../components/Loader';
import GroupedSelect from '../../components/GroupedSelect';
import ErrorMessage from '../ErrorMessage';
import SharedTable from '../SharedTable';
import CreateSharedForm from '../CreateSharedForm';
import style from './style.css';




class App extends Component {
	
	constructor(props) {
    super(props);
  }
	
	componentWillMount() {
		this.props.listSettings();
	}
	
	shouldComponentUpdate(nextProps, nextState) {
		return nextProps.translations !== this.props.translations || nextProps.isFetching !== this.props.isFetching;
	}
	
  render() {
    const { isFetching, translations, children } = this.props;
		
		if ( isFetching ) {
			return (
				<div className={style.wrap}>
					<h2>{ translations.loading }</h2>
				</div>
			);
		}

    return (
      <div className='wrap'>
				<Loader 
					isVisible={ isFetching }
					message={ translations.loading }
				/>
				<h2>{ translations.drafts_title }</h2>
				<ErrorMessage/>
				<h3>{ translations.curently_shared }</h3>
				<SharedTable/>
				<h3>{ translations.drafts_title }</h3>
				<CreateSharedForm/>
        {children}
      </div>
    )
  }
}


App.propTypes = {
	dispatch		: PropTypes.func.isRequired,
	listSettings: PropTypes.func.isRequired,
  isFetching	: PropTypes.bool.isRequired,
  translations: PropTypes.array.isRequired,
};

function mapStateToProps(state) {
	console.log('state to props');
  return state.setting
}

function mapDispatchToProps(dispatch) {
  return {
		listSettings: () => dispatch(listSettings())
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(App)