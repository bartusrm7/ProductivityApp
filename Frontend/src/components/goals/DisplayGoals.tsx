import { useEffect, useState } from "react";
import type { UserGoalsData } from "../../types/goals";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import EditGoal from "./EditGoal";
import DeleteGoal from "./DeleteGoal";

export default function DisplayGoals({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [goalsData, setGoalsData] = useState<UserGoalsData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);
	const [refresh, setRefresh] = useState<number>(0);

	async function getGoalsData() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-goals", {
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
		const response = await fetch(`http://productivityapp.local/sort-goals?status=in_progress&sort=${sortDataKey}&direction=${directionSort}`, {
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
		<div className='display-goals'>
			<div className='d-flex align-items-center border-bottom pb-1'>
				<button className={`display-goals__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
					<IoIosArrowUp size={24} className='display-icon' />
				</button>
				<h4 className='ms-2 mb-0'>In progress</h4>
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
						<div className='col-2'>
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
						<div className='col-2'>
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
						<div className='col-2 text-center'>Actions</div>
					</div>
					{goalsData.map((goal, index) => (
						<div className='display-goals__main-container custom-table-row d-flex flex-wrap align-items-center border-bottom' key={index}>
							<div className='display-goals__id col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
							<div className='display-goals__name display-goal__name-row col-12 col-md-2'>{goal.name}</div>
							<div className='display-goals__description col-12 col-md-3'>{goal.description}</div>
							<div className='display-goals__deadline col-8 col-md-2'>{goal.deadline}</div>
							<div className='display-goals__progress col-8 col-md-2'>{goal.progress}</div>
							<div className='display-goals__buttons-container d-flex justify-content-center col-4 col-md-2'>
								<EditGoal goalProp={goal} refreshData={() => setRefresh(prevState => prevState + 1)} />
								<DeleteGoal goalId={goal.id} refreshData={() => setRefresh(prevState => prevState + 1)} />
							</div>
						</div>
					))}
				</div>
			)}
		</div>
	);
}
