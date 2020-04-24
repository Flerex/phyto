import BoxerModes from '../../BoxerModes';

const mode = (state = BoxerModes.ZOOM, action) => {
    switch (action.type) {
        case 'SET_MODE':
            return action.mode;
        default:
            return state
    }
}

export default mode;
