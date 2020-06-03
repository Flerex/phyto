import {applyMiddleware, createStore} from 'redux';
import reducers from './store/reducers';
import thunkMiddleware from 'redux-thunk'
import ReactDOM from 'react-dom';
import React from 'react';
import Boxer from './Boxer';
import {Provider} from 'react-redux';

const el = document.getElementById('boxer');
if (el) {

    const initialState = {
        boxes: JSON.parse(el.dataset.boxes).map(b => Object.assign(b, {persisted: true})),
        user: JSON.parse(el.dataset.user),
        image: el.dataset.image,
        assignment: el.dataset.assignment,
        canvas: {
            width: 768,
            height: 600,
        },
        catalogs: JSON.parse(el.dataset.catalogs),
        tree: JSON.parse(el.dataset.tree),
    };

    const store = createStore(reducers, initialState, applyMiddleware(thunkMiddleware));

    ReactDOM.render(
        <Provider store={store}>
            <Boxer/>
        </Provider>, el);
}
