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
        this.renderDefaultButtons = this.renderDefaultButtons.bind(this);
    }

    handleEdit() {
        this.props.enableResizing(this.props.box.id);

        if (this.state.resizing) { // Cancel resizing
            this.props.cancelResizing();
        }

        const resizing = !this.state.resizing;
        this.setState({resizing});
    }

    renderDefaultButtons() {
        if (this.state.resizing) return;

        return (
            <>
                <Button onClick={this.handleEdit} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-expand"/></Icon>
                </Button>

                <Button onClick={() => this.props.handleRemove(this.props.box.id)} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-trash-alt"/></Icon>
                </Button>
            </>
        )
    }

    renderResizingButtons() {
        if (!this.state.resizing) return;

        return (
            <>
                <Button onClick={this.props.saveResizing} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-save"/></Icon>
                </Button>
                <Button onClick={this.handleEdit} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-times"/></Icon>
                </Button>
            </>
        )
    }

    render() {
        return (
            <>
                {this.renderDefaultButtons()}
                {this.renderResizingButtons()}
            </>
        )
    }
}
