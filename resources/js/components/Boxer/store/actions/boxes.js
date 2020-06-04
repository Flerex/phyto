const addingBox = (box, user) => ({
    type: 'ADD_BOX',
    box, user
})

const persistBox = (boxOrId) => ({
    type: 'PERSIST_BOX',
    boxOrId
})

const taggingBox = (id, node) => ({
    type: 'TAG_BOX',
    id, node
})

export const tagBox = (id, node) => dispatch => {
    dispatch(taggingBox(id, node))
    axios.post(route('projects.bounding_boxes.tag', {boundingBox: id}), node)
        .then(_ => {
            dispatch(persistBox(id));
        });
}

export const addBox = (box, user, assignment) => dispatch => {
    dispatch(addingBox(box, user))
    axios.post(route('projects.bounding_boxes.store', {assignment}), box)
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

export const setTaggingBox = (id, state) => ({
        type: 'SET_TAGGING_BOX',
        id, state
    }
)

const editingBox = (id, box) => ({
        type: 'EDIT_BOX',
        id, box
    }
)

export const editBox = (id, box) => dispatch => {
    dispatch(editingBox(id, box));
    axios.post(route('projects.bounding_boxes.update', {boundingBox: id}), {
        ...box,
        _method: 'PATCH'
    }).then(({data: {id}}) => {
        dispatch(persistBox(id));
    });
}

export const deleteBox = id => dispatch => {
    dispatch(deletingBox(id));
    axios.post(route('projects.bounding_boxes.destroy', {boundingBox: id}), {_method: 'DELETE'});
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
