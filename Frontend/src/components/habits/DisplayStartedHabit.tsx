import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserHabitDetailsDataJoined } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import SetHabitAsDone from "./SetHabitAsDone";

export default function DisplayStartedHabit({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [habitsDetails, setHabitsDetails] = useState<UserHabitDetailsDataJoined[]>([]);
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

	useEffect(() => {
		getStartedHabits();
	}, [refreshParent, refresh]);

	return (
		<div className='display-started-habit'>
			<div className='d-flex align-items-center border-bottom pb-1'>
				<IoIosArrowUp size={24} />
				<h4 className='ms-2 mb-0'>Started</h4>
			</div>
			<div className='header-custom-table-names d-none d-md-flex border-bottom'>
				<div className='col-1'>#</div>
				<div className='col-2'>Habit</div>
				<div className='col-3'>Description</div>
				<div className='col-1'>Streak</div>
				<div className='col-1'>Days Done</div>
				<div className='col-2'>Date</div>
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
	);
}
