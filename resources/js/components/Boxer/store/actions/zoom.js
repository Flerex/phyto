export const setScale = scale => ({
    type: 'SET_SCALE',
    scale
})

export const addToScale = value => ({
    type: 'ADD_TO_SCALE',
    value
})

export const moveTo = (top, left) => ({
    type: 'MOVE_TO',
    top, left
})

export const moveToRelatively = (top, left) => ({
    type: 'MOVE_TO_RELATIVELY',
    top, left
})

