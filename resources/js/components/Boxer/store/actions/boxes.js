export const addBox = (box, user) => ({
    type: 'ADD_BOX',
    box, user
})


export const persistBox = (boxOrId) => ({
    type: 'PERSIST_BOX',
    boxOrId
})

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

export const editBox = (id, box) => ({
        type: 'EDIT_BOX',
        id, box
    }
)

export const deleteBox = id => ({
        type: 'DELETE_BOX',
        id
    }
)
export const focusBox = (id, focused) => ({
        type: 'FOCUS_BOX',
        id, focused
    }
)
