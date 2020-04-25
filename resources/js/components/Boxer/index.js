import {createStore} from 'redux';
import reducers from './store/reducers';
import ReactDOM from 'react-dom';
import Provider from 'react-redux/lib/components/Provider';
import React from 'react';
import Boxer from './Boxer';

const el = document.getElementById('boxer');
if (el) {

    const initialState = {
        boxes: JSON.parse(el.dataset.boxes).map(b => Object.assign(b, {persisted: true})),
        user: el.dataset.user,
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
