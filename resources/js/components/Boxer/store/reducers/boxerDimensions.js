const boxerDimensions = (state = null, action) => {

    // Default value
    state = state || {
        width: null,
        height: null,
        x: null,
        y: null,
    }

    switch (action.type) {
        case 'UPDATE_DIMENSIONS':
            return {width: action.width, height: action.height, x: action.x, y: action.y};
        default:
            return state
    }
}

export default boxerDimensions;
