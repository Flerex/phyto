import React from 'react'
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import MovementTools from './MovementTools';
import ZoomingTools from './ZoomingTools';
import SizingTools from './SizingTools';
import ModeSwitcher from './ModeSwitcher';

export default function Toolbox() {

    return (
        <div className={styles.toolbox}>
            <ZoomingTools />
            <MovementTools />
            <SizingTools />
            <ModeSwitcher />
        </div>
    )
}
