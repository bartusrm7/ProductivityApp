import { useEffect, useState } from "react";
import type { UserGoalsData } from "../../types/goals";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import EditGoal from "./EditGoal";
import DeleteGoal from "./DeleteGoal";
import DoneGoal from "./DoneGoal";

export default function DisplayGoalsDone({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
    const [goalsData, setGoalsData] = useState<UserGoalsData[]>([]);
    const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
    const [sortDataKey, setSortDataKey] = useState<string>();
    const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);
    const [refresh, setRefresh] = useState<number>(0);

    async function getGoalsData() {
        const jwt = localStorage.getItem("jwt");
        const response = await fetch("http://productivityapp.local/get-goals-done", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${jwt}`,
            },
        });
        const data = await response.json();
        if (data.success) {
            setGoalsData(data.data);
        } else {
        }
    }

    async function sortGoalsFunction() {
        const jwt = localStorage.getItem("jwt");
        const response = await fetch(`http://productivityapp.local/sort-goals?status=done&sort=${sortDataKey}&direction=${directionSort}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${jwt}`,
            },
        });
        const data = await response.json();
        if (data.success) {
            setGoalsData(data.data);
        }
    }

    const handleSortFunction = (e: any) => {
        const key = e.target.value;

        if (sortDataKey === key) {
            setDirectionSort(direction => (direction === "asc" ? "desc" : "asc"));
        } else {
            setDirectionSort("asc");
        }
        setSortDataKey(key);
    };

    useEffect(() => {
        getGoalsData();
    }, [refreshParent, refresh]);

    useEffect(() => {
        if (sortDataKey) {
            sortGoalsFunction();
        }
    }, [directionSort, sortDataKey]);

    useEffect(() => {}, [refreshParent]);

    return (
        <div className='display-goals-done'>
            <div className='d-flex align-items-center border-bottom pb-1'>
                <button className={`display-goals-done__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
                    <IoIosArrowUp size={24} className='display-icon' />
                </button>
                <h4 className='ms-2 mb-0'>Completed</h4>
            </div>
            {isMenuDisplay && (
                <div>
                    <div className='header-custom-table-names d-none d-md-flex border-bottom'>
                        <div className='col-1'>
                            #
                            <button className='sort-btn ms-2' onClick={handleSortFunction} value='id'>
                                {directionSort === "asc" && sortDataKey === "id" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
                            </button>
                        </div>
                        <div className='col-3'>
                            Goal
                            <button className='sort-btn ms-2' onClick={handleSortFunction} value='name'>
                                {directionSort === "asc" && sortDataKey === "name" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
                            </button>
                        </div>
                        <div className='col-3'>
                            Description
                            <button className='sort-btn ms-2' onClick={handleSortFunction} value='description'>
                                {directionSort === "asc" && sortDataKey === "description" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
                            </button>
                        </div>
                        <div className='col-3'>
                            Deadline
                            <button className='sort-btn ms-2' onClick={handleSortFunction} value='deadline'>
                                {directionSort === "asc" && sortDataKey === "deadline" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
                            </button>
                        </div>
                        <div className='col-2'>
                            Progress
                            <button className='sort-btn ms-2' onClick={handleSortFunction} value='progress'>
                                {directionSort === "asc" && sortDataKey === "progress" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
                            </button>
                        </div>
                    </div>
                    {goalsData.map((goal, index) => (
                        <div className='display-goals-done__main-container custom-table-row d-flex flex-wrap align-items-center border-bottom' key={index}>
                            <div className='display-goals-done__id col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
                            <div className='display-goals-done__name display-goal__name-row col-12 col-md-3'>{goal.name}</div>
                            <div className='display-goals-done__description col-12 col-md-3'>{goal.description}</div>
                            <div className='display-goals-done__deadline col-7 col-md-3'>{goal.deadline}</div>
                            <div className='display-goals-done__progress col-5 col-md-2'>{goal.progress}</div>
                            
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
