const mode = (state = [], action) => {
    switch (action.type) {
        case 'ADD_BOX':
            return state.concat({...action.box, persisted: false, user: action.user});
        case 'PERSIST_BOX':
            return persistBox(state, action.boxOrId)
        case 'HIGHLIGHT_BOX':
            return state.map(
                box => boxesAreEquivalent(box, action.box) ? {...box, highlighted: action.highlighted} : box
            );
        case 'SET_EDITING_BOX':
            return setEditingBox(state, action.id, action.state, action.temporalCoordinates);
        case 'EDIT_BOX':
            return state.map(box => box.id === action.id ? {...box, persisted: false, editing: false, ...action.box} : box);
        case 'TAG_BOX':
            return tagBox(state, action.id, action.node);
        case 'SET_TAGGING_BOX':
            return setTaggingBox(state, action.id, action.state);
        case 'FOCUS_BOX':
            return state.map(box => box.id === action.id ? {...box, focused: action.focused} : {
                ...box,
                focused: false
            });
        case 'DELETE_BOX':
            return state.filter(el => !(el.id === action.id));
        default:
            return state
    }
}

/**
 * Reducer that persist a given box by its id or by its coordinates.
 */
function persistBox(state, boxOrId) {
    return state.map(box => {
        if (box.persisted) return box;

        const objectAndEqualCoordinates = (typeof boxOrId === 'object' && boxesAreEquivalent(boxOrId, box));

        // boxOrId can be either an object with the exact coordinates of the box or a id of an already persisted box.
        if (objectAndEqualCoordinates) {
            return {...box, id: boxOrId.id, persisted: true}

        }

        if(box.id === boxOrId) {
            return {...box, persisted: true}
        }

        return box;
    });
}

/**
 * Reducer that sets a box into editing mode.
 *
 * A box in editing mode has it's “editing” property set to true
 * and has a temporalCoordinates property that stores the edited
 * coordinates of the box, prior to saving them.
 */
function setEditingBox(boxes, id, editing, temporalCoordinates) {

    return boxes.map(box => {

        if (box.id !== id) return box;

        temporalCoordinates = temporalCoordinates || {
            top: box.top,
            left: box.left,
            width: box.width,
            height: box.height
        };

        return {
            ...box,
            editing,
            temporalCoordinates: editing ? temporalCoordinates : null
        };

    });
}


/**
 * Reducer that sets a box into tagging mode.
 *
 * A box in editing mode has it's “tagging” property set to true.
 */
function setTaggingBox(boxes, id, tagging) {

    return boxes.map(box => {

        if (box.id !== id) return box;

        return {
            ...box,
            tagging,
        };

    });
}


/**
 * Handle the tagBox action.
 *
 * @param boxes
 * @param id
 * @param taggable
 * @returns {*}
 */
function tagBox(boxes, id, taggable) {
    return boxes.map(box => {

        if (box.id !== id) return box;

        return {
            ...box,
            persisted: false,
            taggable,
        };

    });
}



/**
 * Returns whether two boxes are equivalent or not.
 *
 * Two boxes are equivalent if their top, left, width
 * and height properties are exactly the same.
 */
function boxesAreEquivalent(box1, box2) {
    return box1.width === box2.width && box1.height === box2.height && box1.top === box2.top && box1.left === box2.left;
}


export default mode;
