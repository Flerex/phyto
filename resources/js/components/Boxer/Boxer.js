import React from 'react'
import styles from '../../../sass/components/Boxer/Boxer.scss'
import connect from 'react-redux/lib/connect/connect';
import Canvas from './Canvas/Canvas';
import Toolbox from './Toolbox/Toolbox';
import BoundingBoxList from './Sidebar/BoundingBoxList';

export default function Boxer() {
    return (
        <div className={styles.wrapper}>
            <div>
                <Canvas/>
                <Toolbox/>
            </div>
            <BoundingBoxList/>
        </div>
    )
}
