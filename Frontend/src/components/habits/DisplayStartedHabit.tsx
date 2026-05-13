import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserHabitDetailsDataJoined } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import SetHabitAsDone from "./SetHabitAsDone";
import { CiMenuKebab } from "react-icons/ci";

export default function DisplayStartedHabit() {
	const [habitsDetails, setHabitsDetails] = useState<UserHabitDetailsDataJoined[]>([]);
	const [isOpenMenuActionButtons, setIsOpenMenuActionButtons] = useState<number | null>(null);

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

	const handleOpenMenuWithActionButtons = (habitId: number) => {
		setIsOpenMenuActionButtons(prevState => (prevState === habitId ? null : habitId));
	};

	useEffect(() => {
		getStartedHabits();
	}, []);

	return (
		<div>
			<div className='d-flex align-items-center'>
				<IoIosArrowUp size={24} />
				<h4 className='ms-2 mb-0'>Started</h4>
			</div>
			<div className='d-none d-md-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-2'>Description</div>
				<div className='col-1'>Streak</div>
				<div className='col-1'>Days Done</div>
				<div className='col-2'>Date</div>
				<div className='col-2 text-center'>Actions</div>
			</div>
			{habitsDetails.map((habit, index) => (
				<div className='d-flex flex-wrap border-bottom py-2' key={index}>
					<div className='col-md-1 d-none d-md-block fw-bold'>{index + 1}.</div>
					<div className='col-11 col-md-3'>{habit.name}</div>
					<div className='col-10 col-md-2'>{habit.description}</div>
					<div className='col-1 col-md-1'>{habit.streak_days}</div>
					<div className='col-1 col-md-1'>{habit.amount_days_done}</div>
					<div className='col-11 col-md-2'>{habit.created_at}</div>
					<div className='col-1 d-md-none text-end'>
						<CiMenuKebab size={24} onClick={() => handleOpenMenuWithActionButtons(habit.id)} />
					</div>
					<div className='d-md-none justify-content-center col-12 col-md-2'>
						{isOpenMenuActionButtons === habit.id && (
							<div>
								<SetHabitAsDone habitId={habit.id} amountDaysDone={habit.amount_days_done} />
								<EditHabit habitProp={habit} />
								<DeleteHabit habitId={habit.id} />
							</div>
						)}
					</div>
					<div className='d-none d-md-flex justify-content-center col-2'>
						<SetHabitAsDone habitId={habit.id} amountDaysDone={habit.amount_days_done} />
						<EditHabit habitProp={habit} />
						<DeleteHabit habitId={habit.id} />
					</div>
				</div>
			))}
		</div>
	);
}
