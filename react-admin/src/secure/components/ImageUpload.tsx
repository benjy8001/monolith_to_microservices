import React, {Component} from 'react';
import axios from "axios";

class ImageUpload extends Component<{value: string, imageChangeHandler: any}, any> {
    private image: string;

    constructor(props: any) {
        super(props);
        this.image = '';
    }

    upload = async (files: FileList | null) => {
        if (null === files) return;

        const data = new FormData();
        data.append('image', files[0]);

        const response = await axios.post('upload', data);
        this.image = response.data.url;
        this.props.imageChangeHandler(this.image);
    }

    render() {
        return (
            <div className="input-group">
                <input type="text" className="form-control" name="image"
                       value={this.props.value}
                       onChange={e => {
                           this.image = e.target.value;
                           this.props.imageChangeHandler(this.image);
                       }} />
                <div className="input-group-append">
                    <label className="btn btn-primary">
                        Upload <input type="file" hidden onChange={e => this.upload(e.target.files)} />
                    </label>
                </div>
            </div>
        );
    }
}

export default ImageUpload;