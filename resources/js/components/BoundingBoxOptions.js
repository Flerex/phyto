import React, {Component} from 'react'
import styles from '../../sass/components/BoundingBoxOptions.scss'
import {Button, Icon} from 'react-bulma-components'

export default class BoundingBoxOptions extends Component {

    constructor(props) {
        super(props);

        this.state = {
            resizing: false,
        }

        this.handleEdit = this.handleEdit.bind(this);
    }

    handleEdit() {
        this.props.enableResizing(this.props.box.id);
        this.setState({resizing: !this.state.resizing});
    }

    render() {
        return (
            <Button onClick={this.handleEdit} color="black" size="small"
                    className={styles.button + (this.state.resizing ? ' is-active' : '')}>
                <Icon><i className={`fas ${this.state.resizing ? 'fa-times' : 'fa-pen'}`}/></Icon>
            </Button>
        )
    }
}
