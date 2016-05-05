import { createStore } from 'redux';
import combinedReducers from '../reducers';
import appliedMiddlewares from '../middlewares';

const store = createStore(combinedReducers, appliedMiddlewares);

export default store;