import {createStore} from 'redux';
import reducers from './store/reducers';
import ReactDOM from 'react-dom';
import React from 'react';
import Boxer from './Boxer';
import {Provider} from 'react-redux';

const el = document.getElementById('boxer');
if (el) {

    const initialState = {
        boxes: JSON.parse(el.dataset.boxes).map(b => Object.assign(b, {persisted: true})),
        user: JSON.parse(el.dataset.user),
        image: {
            key: el.dataset.imageKey,
            url: el.dataset.image,
        },
        canvas: {
            width: 768,
            height: 600,
        },
    };

    const store = createStore(reducers, initialState);

    ReactDOM.render(
        <Provider store={store}>
            <Boxer/>
        </Provider>, el);
}
