import {combineActions} from 'redux';
import boxerDimensions from './boxerDimensions';
import mode from './mode';
import zoom from './zoom';
import boxes from './boxes';

export default combineActions({
    boxerDimensions,
    mode,
    zoom,
    boxes
})
