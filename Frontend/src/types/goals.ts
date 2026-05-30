export interface UserCreateGoalData {
	name: string;
	type: string;
	created_at: string;
}

export interface UserGoalsData {
	id: number;
	name: string;
	description: string;
	status: string;
	type: string;
	progress: number;
	created_at: string;
	deadline: string;
}
