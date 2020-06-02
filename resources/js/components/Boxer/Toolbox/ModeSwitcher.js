import React from 'react'
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import {Button, Icon} from 'react-bulma-components';
import {setMode} from '../store/actions/mode';
import BoxerModes from '../BoxerModes';
import {useDispatch, useSelector} from 'react-redux';

export default function ModeSwitcher() {

    const mode = useSelector(s => s.mode);
    const dispatch = useDispatch();

    const changeMode = mode => {
        dispatch(setMode(mode))
    }

    return (
        <Button.Group className={styles.buttonGroup} hasAddons={true} style={{marginLeft: 'auto'}}>
            <Button rounded={true} onClick={() => changeMode(BoxerModes.DRAW)} size="small" className={styles.button}
                    color={mode === BoxerModes.DRAW ? 'link' : null} title={Lang.trans('boxer.draw_mode')}>
                <Icon><i className="fas fa-expand"/></Icon>
            </Button>
            <Button rounded={true} onClick={() => changeMode(BoxerModes.EDIT)} size="small" className={styles.button}
                    color={mode === BoxerModes.EDIT ? 'link' : null} title={Lang.trans('boxer.edit_mode')}>
                <Icon><i className="fas fa-pen"/></Icon>
            </Button>
            <Button rounded={true} onClick={() => changeMode(BoxerModes.ZOOM)} size="small" className={styles.button}
                    color={mode === BoxerModes.ZOOM ? 'link' : null} title={Lang.trans('boxer.zoom_mode')}>
                <Icon><i className="fas fa-mouse-pointer"/></Icon>
            </Button>
        </Button.Group>
    )
}
