import React, {Component} from 'react'
import styles from '../../sass/components/BoundingBox.scss'

export default class BoundingBox extends Component {

    constructor(props) {
        super(props)

        this.getBoundingBoxStyle = this.getBoundingBoxStyle.bind(this);
    }


    getBoundingBoxStyle(box) {
        return {
            width: box.width + 'px',
            height: box.height + 'px',
            top: box.top + 'px',
            left: box.left + 'px',
        }
    }

    render() {
        const className = `${styles.boundingBox}  ${this.props.highlighted ? styles.highlightedBox : ''}`;

        return (
            <div className={className} style={this.getBoundingBoxStyle(box)}
            />
        )
    }


}
