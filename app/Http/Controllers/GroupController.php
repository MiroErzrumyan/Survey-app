<?php

namespace App\Http\Controllers;

use App\Contracts\GroupContract;
use App\Services\GroupService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GroupController extends Controller
{

    /**
     * @param GroupContract $groupRepository
     * @param GroupService $groupService
     */
    public function __construct(protected GroupContract $groupRepository, protected GroupService $groupService)
    {
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request)
    {
        $groupId = session()->get('group_id');
        if(!$groupId) {
            $group = $this->groupRepository->getRandomGroupWithRelations(['questions', 'questions.answers']);
            $question = $group->questions->first();
            session()->put('group_id', $group->id);
        }
        else {
            $group = $this->groupRepository->getByIdWithRelations($groupId,['questions', 'questions.answers','selectedGroupAnswers']);

            if ($group->selectedGroupAnswers->last()) {
                $lastAnsweredQuestionId = $group->selectedGroupAnswers->last()->question_id;
                $question = $group->questions->where('id', ">" ,$lastAnsweredQuestionId )->first();
            }else {
                $question = $group->questions->first();
            }
        }

        $availablePoints = $group->selectedGroupAnswers()->sum('answer_point');

        return view('survey', ['question' => $question, 'groupId' => $group->id,'availablePoints' => $availablePoints]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeAnswer(Request $request): \Illuminate\Http\RedirectResponse
    {
        $requestData = $request->all();
        $data = $this->groupService->makeAnswerData($requestData);
        $requestGroupId = $request['group_id'];
        $requestAnswerId = $request['answer_id'] ?? null;
        $this->groupRepository->storeGroupAnswers($requestGroupId,$requestAnswerId,$data);

        $groupId = session()->get('group_id');
        if (!$groupId) {
            session()->put('group_id', $requestGroupId);
        }

        return redirect()->route('group.index');
    }


    /**
     * @param Request $request
     * @param $groupId
     * @return RedirectResponse
     */
    public function startOver(Request $request, $groupId): RedirectResponse
    {
        $this->groupRepository->deleteGroupAnswers($groupId);
        session()->remove('group_id');

        return redirect()->back();
    }
}
