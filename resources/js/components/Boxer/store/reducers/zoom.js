const validScale = scale => Math.max(Math.min(2, scale), .125)


const zoom = (state = null, action) => {
    // Default value
    state = state || {
        scale: 1,
        position: {
            top: 0,
            left: 0,
        },
    }

    switch (action.type) {
        case 'SET_SCALE':
            return {...state, scale: validScale(action.scale)};
        case 'ADD_TO_SCALE':
            return {...state, scale: validScale(state.scale + action.value)};
        case 'MOVE_TO':
            return {...state, position: {top: action.top, left: action.left}};
        case 'MOVE_TO_RELATIVELY':
            return {...state, position: {top: state.position.top + action.top, left: state.position.left + action.left}};
        default:
            return state;
    }
}

export default zoom;
