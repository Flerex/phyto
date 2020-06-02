const addingBox = (box, user) => ({
    type: 'ADD_BOX',
    box, user
})

const persistBox = (boxOrId) => ({
    type: 'PERSIST_BOX',
    boxOrId
})

export const addBox = (box, user, image) => dispatch => {
    dispatch(addingBox(box, user))
    axios.post(route('async.bounding_boxes.store', {image}), box)
        .then(({data}) => {
            dispatch(persistBox(data));
        });
}

export const highlightBox = (box, highlighted) => ({
        type: 'HIGHLIGHT_BOX',
        box, highlighted
    }
)

export const setEditingBox = (id, state, temporalCoordinates = null) => ({
        type: 'SET_EDITING_BOX',
        id, state, temporalCoordinates
    }
)

const editingBox = (id, box) => ({
        type: 'EDIT_BOX',
        id, box
    }
)

export const editBox = (id, box) => dispatch => {
    dispatch(editingBox(id, box));
    axios.post(route('async.bounding_boxes.update', {boundingBox: id}), {
        ...box,
        _method: 'PATCH'
    }).then(({data: {id}}) => {
        dispatch(persistBox(id));
    });
}

export const deleteBox = id => dispatch => {
    dispatch(deletingBox(id));
    axios.post(route('async.bounding_boxes.destroy', {boundingBox: id}), {_method: 'DELETE'});
}

const deletingBox = id => ({
    type: 'DELETE_BOX',
    id
})

export const focusBox = (id, focused) => ({
        type: 'FOCUS_BOX',
        id, focused
    }
)
