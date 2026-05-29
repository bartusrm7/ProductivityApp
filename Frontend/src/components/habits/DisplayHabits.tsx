import { useEffect, useState } from "react";
import type { UserHabitData } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import StartHabit from "./StartHabit";

export default function DisplayHabits({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [habitsData, setHabitsData] = useState<UserHabitData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);
	const [refresh, setRefresh] = useState<number>(0);

	async function getAllHabits() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-habits", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsData(data.data);
		}
	}

	async function sortHabitsFunction() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/sort-habits?status=in_progress&sort=${sortDataKey}&direction=${directionSort}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsData(data.data);
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
		getAllHabits();
	}, [refreshParent, refresh]);

	useEffect(() => {
		if (sortDataKey) {
			sortHabitsFunction();
		}
	}, [directionSort, sortDataKey]);

	return (
		<div className='display-habits'>
			<div className='d-flex align-items-center border-bottom pb-1'>
				<button className={`display-habits__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
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
						<div className='col-3'>
							Habit
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
							Date
							<button className='sort-btn ms-2' onClick={handleSortFunction} value='created_at'>
								{directionSort === "asc" && sortDataKey === "created_at" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
							</button>
						</div>
						<div className='col-3 text-center'>Actions</div>
					</div>
					{habitsData.map((habit, index) => (
						<div className='display-habits__main-container custom-table-row d-flex flex-wrap align-items-center border-bottom' key={index}>
							<div className='display-habits__id col-1 d-none d-md-block fw-bold'>{index + 1}.</div>
							<div className='display-habits__name display-habit__name-row col-12 col-md-3'>{habit.name}</div>
							<div className='display-habits__description col-12 col-md-3'>{habit.description}</div>
							<div className='display-habits__created_at col-8 col-md-2'>{habit.created_at}</div>
							<div className='display-habits__buttons-container d-flex justify-content-center col-4 col-md-3'>
								<StartHabit habitId={habit.id} refreshData={refreshData} />
								<EditHabit habitProp={habit} refreshData={() => setRefresh(prevState => prevState + 1)} />
								<DeleteHabit habitId={habit.id} refreshData={() => setRefresh(prevState => prevState + 1)} />
							</div>
						</div>
					))}
				</div>
			)}
		</div>
	);
}
