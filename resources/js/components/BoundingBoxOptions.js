import React, {Component} from 'react'
import styles from '../../sass/components/BoundingBoxOptions.scss'
import {Button, Icon} from 'react-bulma-components'

export default class BoundingBoxOptions extends Component {

    constructor(props) {
        super(props);

        this.state = {
            resizing: false,
        };

        this.handleEdit = this.handleEdit.bind(this);
    }

    handleEdit() {
        this.props.enableResizing(this.props.box.id);

        if (this.state.resizing) { // Cancel resizing
            this.props.cancelResizing();
        }

        const resizing = !this.state.resizing;
        this.setState({resizing});
    }

    render() {
        return (
            <>
                {this.state.resizing && (
                    <Button onClick={this.props.saveResizing} color="black" size="small" className={styles.button}>
                        <Icon><i className="fas fa-save"/></Icon>
                    </Button>
                )}
                <Button onClick={this.handleEdit} color="black" size="small" className={styles.button}>
                    <Icon><i className={`fas ${this.state.resizing ? 'fa-times' : 'fa-expand'}`}/></Icon>
                </Button>
            </>
        )
    }
}
