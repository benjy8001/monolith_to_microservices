import React, {Component} from "react";
import Wrapper from "../Wrapper";
import c3 from 'c3';
import axios from "axios";
import constants from "../../constants";

class Dashboard extends Component<any, any> {
    componentDidMount = async () => {
        let chart = c3.generate({
            bindto: '#chart',
            data: {
                x: 'x',
                columns: [
                    ['x'],
                    ['Sales'],
                ],
                types: {
                    Sales: 'bar'
                }
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%Y-%m-%d'
                    }
                }
            }
        });

        const response = await axios.get(`${constants.BASE_URL}/chart`);
        const chartData: {date: string, sum: number}[] = response.data.data;

        chart.load({
            columns: [
                ['x', ...chartData.map(r => r.date)],
                ['Sales', ...chartData.map(r => r.sum)],
            ]
        });
    }

    render() {
        return (
            <Wrapper>
                <div className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 className="h1">Dashboard</h1>
                </div>
                <div>
                    <h2 className="h2">Daily sales</h2>
                    <div id="chart" />
                </div>
            </Wrapper>
        );
    }
}

export default Dashboard;