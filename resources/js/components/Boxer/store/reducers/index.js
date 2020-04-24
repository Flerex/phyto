import {combineReducers} from 'redux';
import boxerDimensions from './boxerDimensions';
import mode from './mode';
import zoom from './zoom';
import user from './user';
import boxes from './boxes';
import image from './image';
import canvas from './canvas';

export default combineReducers({boxerDimensions, mode, zoom, user, boxes, image, canvas})
