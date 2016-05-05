import { applyMiddleware } from 'redux';
import thunk from 'redux-thunk'; //https://github.com/gaearon/redux-thunk
import createLogger from 'redux-logger'; //https://github.com/fcomb/redux-logger
import api from './api';

const loggerMiddleware = createLogger();

var appliedMiddlewares;

if (process.env.NODE_ENV !== 'production') {
  appliedMiddlewares = applyMiddleware(
    thunk,
    api,
    loggerMiddleware
  );
} else {
  appliedMiddlewares = applyMiddleware(
    thunk,
    api
  );
}

export default appliedMiddlewares;