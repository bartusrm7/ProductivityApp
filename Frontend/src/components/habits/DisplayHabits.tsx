import { useEffect, useState } from "react";
import type { UserHabitData } from "../../types/habits";
import EditHabit from "./EditHabit";
import DeleteHabit from "./DeleteHabit";
import { IoIosArrowUp } from "react-icons/io";
import { CiMenuKebab } from "react-icons/ci";
import StartHabit from "./StartHabit";

export default function DisplayHabits() {
	const [habitsData, setHabitsData] = useState<UserHabitData[]>([]);
	const [isOpenMenuActionButtons, setIsOpenMenuActionButtons] = useState<number | null>(null);

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

	const handleOpenMenuWithActionButtons = (habitId: number) => {
		setIsOpenMenuActionButtons(prevState => (prevState === habitId ? null : habitId));
	};

	useEffect(() => {
		getAllHabits();
	}, []);

	return (
		<div>
			<div className='d-flex align-items-center'>
				<IoIosArrowUp size={24} />
				<h4 className='ms-2 mb-0'>Done</h4>
			</div>
			<div className='d-none d-md-flex fw-bold border-bottom py-2'>
				<div className='col-1'>#</div>
				<div className='col-3'>Habit</div>
				<div className='col-3'>Description</div>
				<div className='col-2'>Date</div>
				<div className='col-3 text-center'>Actions</div>
			</div>
			{habitsData.map((habit, index) => (
				<div className='d-flex flex-wrap align-items-center border-bottom py-2' key={index}>
					<div className='col-1 d-none d-md-block'>{habit.id}</div>
					<div className='display-habit__name-row col-11 col-md-3'>{habit.name}</div>
					<div className='col-1 d-md-none text-end'>
						<CiMenuKebab size={24} onClick={() => handleOpenMenuWithActionButtons(habit.id)} />
					</div>
					<div className='col-9 col-md-3'>{habit.description}</div>
					<div className='col-2 d-none d-md-flex'>{habit.created_at}</div>
					<div className='d-md-none justify-content-center col-3'>
						{isOpenMenuActionButtons === habit.id && (
							<div>
								<StartHabit habitId={habit.id} />
								<EditHabit habitProp={habit} />
								<DeleteHabit habitId={habit.id} />
							</div>
						)}
					</div>
					<div className='d-none d-md-flex justify-content-center col-3'>
						<StartHabit habitId={habit.id} />
						<EditHabit habitProp={habit} />
						<DeleteHabit habitId={habit.id} />
					</div>
				</div>
			))}
		</div>
	);
}
