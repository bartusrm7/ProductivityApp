import { useEffect, useState } from "react";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import type { UserHabitDetailsDataJoined } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import SetHabitAsDone from "./SetHabitAsDone";

export default function DisplayStartedHabit({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [habitsDetails, setHabitsDetails] = useState<UserHabitDetailsDataJoined[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);
	const [refresh, setRefresh] = useState<number>(0);

	async function getStartedHabits() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-started-habits?status=started", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsDetails(data.data);
		}
	}

	async function sortHabitsFunction() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/sort-habits?status=started&sort=${sortDataKey}&direction=${directionSort}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setHabitsDetails(data.data);
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
		getStartedHabits();
	}, [refreshParent, refresh]);

	useEffect(() => {
		if (sortDataKey) {
			sortHabitsFunction();
		}
	}, [directionSort, sortDataKey]);

	return (
		<div className='display-started-habit'>
			<div className='d-flex align-items-center border-bottom pb-1'>
				<button className={`display-started-habit__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
					<IoIosArrowUp size={24} className='display-icon' />
				</button>
				<h4 className='ms-2 mb-0'>Started</h4>
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
						<div className='col-1'>
							Streak
							<button className='sort-btn ms-2' onClick={handleSortFunction} value='streak_days'>
								{directionSort === "asc" && sortDataKey === "streak_days" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
							</button>
						</div>
						<div className='col-1'>
							Days Done
							<button className='sort-btn ms-2' onClick={handleSortFunction} value='amount_days_done'>
								{directionSort === "asc" && sortDataKey === "amount_days_done" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
							</button>
						</div>
						<div className='col-2'>
							Date
							<button className='sort-btn ms-2' onClick={handleSortFunction} value='created_at'>
								{directionSort === "asc" && sortDataKey === "created_at" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
							</button>
						</div>
						<div className='col-2 text-center'>Actions</div>
					</div>
					{habitsDetails.map((habit, index) => (
						<div className='display-started-habit__main-container custom-table-row d-flex flex-wrap border-bottom' key={index}>
							<div className='display-started-habit__id habit-started-order col-md-1 d-none d-md-block fw-bold'>{index + 1}.</div>
							<div className='display-started-habit__name habit-started-order col-9 col-md-2'>{habit.name}</div>
							<div className='display-started-habit__description habit-started-order col-10 col-md-3'>{habit.description}</div>
							<div className='display-started-habit__streak_days habit-started-order text-end text-md-start col-2 col-md-1'>
								<span className='me-1'>🔥</span>
								{habit.streak_days}
							</div>
							<div className='display-started-habit__amount_days_done habit-started-order text-end text-md-start col-1 col-md-1'>{habit.amount_days_done}</div>
							<div className='display-started-habit__created_at col-8 habit-started-order col-md-2'>{habit.created_at}</div>
							<div className='display-started-habit__buttons-container habit-started-order col-4 d-flex justify-content-end justify-content-md-center col-md-2'>
								<SetHabitAsDone habitId={habit.id} amountDaysDone={habit.amount_days_done} refreshData={() => setRefresh(prevState => prevState + 1)} />
								<EditHabit habitProp={habit} refreshData={() => setRefresh(prevState => prevState + 1)} />
								<DeleteHabit habitId={habit.habit_id} refreshData={() => setRefresh(prevState => prevState + 1)} />
							</div>
						</div>
					))}
				</div>
			)}
		</div>
	);
}
