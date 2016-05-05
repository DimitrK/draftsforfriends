'use strict';
import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import { listShared } from '../../actions/shared-actions';
import SharedRow from '../../components/SharedRow';
import Togglable from '../../components/Togglable';
import DestroySharedLink from '../DestroySharedLink';
import Loader from '../../components/Loader';
import ExtendSharedForm from '../ExtendSharedForm';
import style from './style.css';

	
class SharedTable extends Component {
	
	componentWillMount() {
		this.props.listShared();
	}
	
	renderTableBody() {
		
		const { translations, shared } = this.props;
		
		if ( shared && shared.length > 0 ) {
			return (
				<tbody>
					{ shared.map( sharedSingular => (
					 		<SharedRow shared={ sharedSingular }>
								<td className={style.actions}>
									<Togglable
										hideText={ translations.cancel_button }
										showText={ translations.extend_button }
										view={ (
											<ExtendSharedForm
												sharedKey={ sharedSingular.shared_key }
											/>
										) }
									/>
								</td>
								<td>
									<DestroySharedLink sharedKey={ sharedSingular.shared_key } />
								</td>
							</SharedRow> 
						)	
					) }
				</tbody>
			);
		} else {
			return (
				<tbody>
					<tr>
						<td colspan="5">{ translations.no_drafts }</td>
					</tr>
				</tbody>
			);
		}
	
	}
	
	render() {
		const { translations, isFetching } = this.props;

		return (
			<div>
				<Loader
					isVisible={ isFetching }
					message={ translations.loading }
				/>
				<table className="widefat">
					<thead>
						<tr>
							<th>{ translations.id_column }</th>
							<th>{ translations.title_column }</th>
							<th>{ translations.link_column }</th>
							<th>{ translations.expires_column }</th>
							<th colSpan="2" className={style.actions}>{ translations.actions_column }</th>
						</tr>
					</thead>
					{ this.renderTableBody() }
				</table>
			</div>
		);
	}
}
						

SharedTable.propTypes = {
	dispatch		: PropTypes.func.isRequired,
	listShared	: PropTypes.func.isRequired,
  isFetching	: PropTypes.bool.isRequired,
  shared			: PropTypes.array.isRequired,
	translations: PropTypes.array.isRequired,
};

const mapStateToProps = (state) => {
	const { translations } = state.setting;
	return {
		translations,
		...state.shared
	}
}	

const mapDispatchToProps = (dispatch) => {
	return {
		listShared: () => dispatch(listShared())
	}
};

export default connect( mapStateToProps, mapDispatchToProps )( SharedTable );
	